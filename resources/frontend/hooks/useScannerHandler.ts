import { useCallback } from "react";

import { RequiredFields } from "types/misc";
import {
    Attendance,
    Attender,
    Event,
    Team,
    TeamAttendance,
    TeamMember,
    TeamMemberAttendance,
    TeamMemberRole,
    User,
    UserAttendance,
    isAttenderTeam,
    isTeamAttendance,
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
    event: Pick<Event, "id">;
    teamMemberPrompt: (member: TeamMember) => Promise<boolean>;
    onSuccess?: (attendance: User | Team) => any;
    onError?: (error: ScannerError) => any;
}) => {
    const [getTeam] = teamAPI.useLazyGetTeamQuery();
    const [attend] = eventAPI.useAttendMutation();
    const [teamMemberAttend] = eventAPI.useTeamMemberAttendMutation();

    return useCallback(
        async (scanvalue: string) => {
            let attendance:
                | RequiredFields<TeamAttendance, "team">
                | RequiredFields<UserAttendance, "user">;
            try {
                attendance = await attend({
                    event: event,
                    attender: scanvalue,
                    present: true,
                }).unwrap();
            } catch (e) {
                /* TODO: Handle error
                
                
                403: Permission denied
                409: (Conflict): Already signed up & is present
                ERROR FORMAT
                /* {
                   message: "Hungarian error message"
                } */

                return;
            }
            if (!isTeamAttendance(attendance)) {
                onSuccess?.(attendance.user);
                return;
            }

            let team: Team;
            try {
                team = await getTeam({ code: scanvalue }).unwrap();
            } catch (e) {
                /*
                 404: Team not found (noop)
                 403: Permission denied (low priority)
                    you can only get team info if:
                        - you are a member of the team
                        - you are a SCN of an event that the team is signed up for 
                 
                 */
                return;
            }

            if (team.members === undefined || team.members.length === 0) {
                onError?.("TeamEmpty");
                return;
            }
            let memberAttendances: TeamMemberAttendance[] = [];

            for (const member of team.members.filter(
                (member) => member.pivot.role !== TeamMemberRole.invited,
            )) {
                memberAttendances.push({
                    is_present: await teamMemberPrompt(member),
                    attendance_id: attendance.id,
                    user_id: member.id,
                });
            }

            try {
                await teamMemberAttend({ data: memberAttendances }).unwrap();
            } catch (e) {
                /* TODO: Handle error

                    403: Permission denied
                    attendance_id must belong to an event that the submitting user is SCN of.
                    
                */
                return;
            }
            onSuccess?.(attendance.team);
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
