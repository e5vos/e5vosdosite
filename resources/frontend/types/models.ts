import { RequiredAndOmitFields, RequiredFields } from "./misc";

export const PermissionCode = {
    organiser: "ORG",
    scanner: " SCN",
    admin: "ADM",
    teacher: "TCH",
    student: "STD",
    operator: "OPT",
    teacheradmin: "TAD",
} as const;

export type PermissionCodeType =
    (typeof PermissionCode)[keyof typeof PermissionCode];

export interface Permission {
    user_id: number;
    event_id?: number | null;
    code: PermissionCodeType;
}

export interface TeamMemberAttendance {
    user_id: number;
    attendance_id: number;
    is_present: boolean;
}

export interface User {
    e5code: string | null;
    name: string;
    id: number;
    ejg_class: string | null;
    teams?: Team[];
    permissions?: Permission[];
    activity?: Attendance[];
    img_url?: string;
    email?: string;
}
export type UserStub = Required<Pick<User, "id" | "name" | "ejg_class">>;

export const isTeam = (team: any): team is Team => {
    return team.code !== undefined;
};
interface BasicAttendance {
    id: number;
    is_present: boolean;
    rank: number | null;
    event_id: number;
}
export interface UserAttendancePivot extends BasicAttendance {
    user_id: number;
}
export interface TeamAttendancePivot extends BasicAttendance {
    team_code: string;
    member_attendances: TeamMemberAttendance[];
}

export type TeamAttendance = RequiredAndOmitFields<
    Team,
    "members",
    "activity"
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

export const TeamMemberRole = {
    invited: "meghívott",
    member: "tag",
    leader: "vezető",
} as const;

export type TeamMemberRoleType =
    (typeof TeamMemberRole)[keyof typeof TeamMemberRole];

export type TeamMembership = {
    team_code: string;
    user_id: number;
    role: TeamMemberRoleType;
};
export type TeamMember = RequiredFields<
    Omit<User, "teams" | "attendances">,
    "ejg_class" | "id" | "name"
> & {
    pivot: TeamMembership;
};
export interface Team {
    name: string;
    code: string;
    description: string;
    members?: TeamMember[];
    activity?: TeamAttendance[];
}
export const SlotType = {
    Presentation: "Előadássáv",
    Program: "Programsáv",
} as const;

export type SlotTypeType = (typeof SlotType)[keyof typeof SlotType];

export interface Slot {
    id: number;
    starts_at: string;
    ends_at: string;
    slot_type: SlotTypeType;
    name: string;

    events?: Event[];
}

export const SignupType = {
    Individual: "user",
    Team: "team",
    Both: "team_user",
} as const;

export type SignupTypeType = (typeof SignupType)[keyof typeof SignupType];

export interface Event {
    id: number;
    name: string;
    description: string;
    organiser: string;
    capacity: number | null;
    occupancy: number;
    slot_id: number | null;
    location_id?: number;
    is_competition: boolean;
    img_url?: string | null;
    signup_deadline: string | null;
    starts_at: string;
    ends_at: string;
    direct_child?: number | null;
    direct_child_slot_id?: number | null;
    root_parent?: number | null;
    root_parent_slot_id?: number | null;
    signup_type: SignupTypeType;
    min_team_size?: number | null;
    max_team_size?: number | null;

    attendances?: Attendance[];

    slot?: Slot;
    location?: Location;
}

export type EventStub = RequiredAndOmitFields<
    Event,
    "slot" | "location",
    "attendances"
>;

export const FloorType = {
    basement: "alagsor",
    ground: "földszint",
    half: "félemelet",
    first: "első emelet",
    second: "második emelet",
    third: "harmadik emelet",
} as const;
export type FloorTypeType = (typeof FloorType)[keyof typeof FloorType];

export interface Location {
    id: number;
    name: string;
    floor: FloorTypeType;
    events?: Event[];
    currentEvent?: Event;
}

export interface Presentation extends Event {
    slot_id: number;
}

export const isTeamCode = (code: string): boolean =>
    code.match(/^[a-zA-Z]{1,10}$/) !== null;

export const isE5Code = (code: string): boolean =>
    code.match(/^20[0-9]{2}[A-FN][0-9]{2}EJG[0-9]{3}$/) !== null;
