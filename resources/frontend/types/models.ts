import { RequiredFields } from "./misc";

export interface Permission {
    user_id: number;
    event_id: number;
    code: string;
}

export interface User {
    e5code: string;
    name: string;
    id: number;
    ejg_class?: string;
    teams?: Team[];
    permissions?: Permission[];
    presentations?: Event[];
    activity?: { event: Event; attendance: UserAttendance }[];
    img_url?: string;
    email?: string;
}

export const isTeam = (team: any): team is Team => {
    return team.code !== undefined;
};
interface BasicAttendance {
    id: number;
    is_present: boolean;
    place: number | null;
    event_id: number;
}
export interface UserAttendancePivot extends BasicAttendance {
    user_id: number;
}
export interface TeamAttendancePivot extends BasicAttendance {
    team_code: string;
}

export type TeamAttendance = RequiredFields<
    Omit<Team, "activity">,
    "members"
> & {
    pivot: TeamAttendancePivot;
};
export type UserAttendance = Omit<User, "activity"> & {
    pivot: UserAttendancePivot;
};

export type Attendance = UserAttendance | TeamAttendance;
export const isTeamAttendancePivot = (
    attendance: any,
): attendance is TeamAttendancePivot => {
    return attendance.team_code !== undefined;
};
export const isUserAttendancePivot = (
    attendance: any,
): attendance is UserAttendancePivot => {
    return attendance.user_id !== undefined;
};

export const isUserAttendance = (
    attendance: any,
): attendance is UserAttendance => {
    return isUserAttendancePivot(attendance.pivot);
};

export const isTeamAttendance = (
    attendance: any,
): attendance is TeamAttendance => {
    return isTeamAttendancePivot(attendance.pivot);
};

export type UserRole = "operator" | "admin" | "user";
export type TeamMemberRole = "meghívott" | "tag" | "vezető";
export type TeamMembership = {
    team_code: string;
    user_id: number;
    role: TeamMemberRole;
};
export type TeamMember = Required<
    Pick<User, "ejg_class" | "email" | "id" | "name">
> & { pivot: TeamMembership };
export interface Team {
    name: string;
    code: string;
    description: string;
    members?: TeamMember[];
    activity?: { event: Event; attendance: TeamAttendance }[];
}
export interface BaseActivity {
    event: Event;
    attendance: Attendance;
}

export const SlotType = {
    Presentation: "Előadássáv",
    Program: "Programsáv",
} as const;

export interface Slot {
    id: number;
    starts_at: string;
    ends_at: string;
    events?: Event[];
    slot_type: (typeof SlotType)[keyof typeof SlotType];
    name: string;
}

export interface Event {
    name: string;
    id: number;
    description: string;
    organiser: string;
    capacity?: number | null;
    occupancy: number;
    attendees?: Attendance[] | null;
    slot?: Slot;
    slot_id?: number | null;
    location_id: number | null;
    location?: Location;
    is_competition: boolean;
    img_url?: string | null;
    signup_deadline?: string | null;
    starts_at: string;
    ends_at: string;
    direct_child?: boolean | null;
    direct_child_slot_id?: number | null;
    root_parent?: boolean | null;
    root_parent_slot_id?: number | null;

    //TODO
}

export interface Location {
    id: number;
    name: string;
}

export interface Presentation extends Event {
    slot_id: number;
}
export interface Challange extends Event {}
