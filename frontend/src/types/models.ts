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
}

export const isTeam = (team: any): team is Team => {
  return team.code !== undefined;
};
interface BasicAttendance {
  is_present: boolean;
  rank: number | null;
  event_id: number;
}
export interface UserAttendancePivot extends BasicAttendance {
  user_id: number;
}
export interface TeamAttendancePivot extends BasicAttendance {
  team_code: string;
}

export type TeamAttendance = Team & { pivot: TeamAttendancePivot };
export type UserAttendance = User & { pivot: UserAttendancePivot };

export type Attendance = UserAttendance | TeamAttendance;
export const isTeamAttendancePivot = (
  attendance: any
): attendance is TeamAttendancePivot => {
  return attendance.team_code !== undefined;
};
export const isUserAttendancePivot = (
  attendance: any
): attendance is UserAttendancePivot => {
  return attendance.user_id !== undefined;
};

export const isUserAttendance = (
  attendance: any
): attendance is UserAttendance => {
  return attendance.e5code !== undefined;
};

export const isTeamAttendance = (
  attendance: any
): attendance is TeamAttendance => {
  return isTeamAttendancePivot(attendance.pivot);
};

export type UserRole = "operator" | "admin" | "user";
export type TeamMemberRole = "captain" | "member" | "invited";
export type TeamMembership = { user: User; role: TeamMemberRole };
export interface Team {
  name: string;
  code: string;
  description: string;
  members: TeamMembership[];
}
export interface BaseActivity {
  event: Event;
  attendance: Attendance;
}

export interface Slot {
  id: number;
  start: string;
  end: string;
  events?: Event[];
  slot_type: "Előadássáv" | "Programsáv";
  name: string;
}

export interface Event {
  name: string;
  id: string;
  description: string;
  organiser: string;
  capacity: number;
  attendees?: Attendance[];
  slot?: Slot;
  slot_id?: number;
  location_id: number | null;
  location?: Location;
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