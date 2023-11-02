import { useCallback } from "react";

import {
    Attendance,
    Event,
    TeamMember,
    TeamMemberAttendance,
    isTeamCode,
} from "types/models";

import eventAPI from "lib/api/eventAPI";
import teamAPI from "lib/api/teamAPI";

export type ScannerError =
    | "NoE5N"
    | "SignupRequired"
    | "PermissionDenied"
    | "TeamEmpty";

const useScannerHandler = ({
    event,
    teamMemberPrompt,
    onSuccess,
    onError,
}: {
    event: Event;
    teamMemberPrompt: (member: TeamMember) => boolean;
    onSuccess?: (attendance: Attendance) => any;
    onError?: (error: ScannerError) => any;
}) => {
    const [getTeam] = teamAPI.useLazyGetTeamQuery();
    const [attend] = eventAPI.useAttendMutation();
    const [teamMemberAttend] = eventAPI.useTeamMemberAttendMutation();

    return useCallback(
        async (scanvalue: string) => {
            let attendance;
            try {
                attendance = await attend({
                    event: event,
                    attender: scanvalue,
                }).unwrap();
            } catch (e) {
                // TODO: Handle error
                return;
            }
            if (!isTeamCode(scanvalue)) {
                onSuccess?.(attendance);
                return;
            }

            const team = await getTeam({ code: scanvalue }).unwrap();
            if (team.members === undefined || team.members.length === 0) {
                onError?.("TeamEmpty");
                return;
            }
            const memberAttendances: TeamMemberAttendance[] = team.members.map(
                (member) => ({
                    is_present: teamMemberPrompt(member),
                    team_code: team.code,
                    user_id: member.id,
                }),
            );
            try {
                await teamMemberAttend(memberAttendances).unwrap();
            } catch (e) {
                // TODO: Handle error
                return;
            }
            onSuccess?.(attendance);
        },
        [
            attend,
            event,
            getTeam,
            onError,
            onSuccess,
            teamMemberAttend,
            teamMemberPrompt,
        ],
    );
};
export default useScannerHandler;
