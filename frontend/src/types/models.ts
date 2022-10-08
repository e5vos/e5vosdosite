export interface User {
  first_name: string;
  last_name: string;
  id: number;
  class: string;
}
interface BasicAttendance {
  present: boolean;
  created_at: string;
  updated_at: string;
  signup_at: string;
  scan_at: string;
  place?: number;
  point: number;
}
export interface IndivitualAttendance extends BasicAttendance {
  user: User;
}
export interface TeamAttendance extends BasicAttendance {
  team: Team;
  users: User[];
}

export const isTeamAttendance = (
  attendance: IndivitualAttendance | TeamAttendance
): attendance is TeamAttendance => {
  return (attendance as TeamAttendance).team !== undefined;
};

export type Attendance = IndivitualAttendance | TeamAttendance;
export type Role = "operator" | "admin" | "user";
export interface Team {
  name: string;
  code: string;
  description: string;
  members: { user: User; role: Role }[];
}
export interface Event {
  name: string;
  id: string;
  description: string;
  organiser: string;
  capacity: number;
  //TODO
}

export interface Presentation extends Event{}
export interface Challange extends Event{}