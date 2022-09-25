export type User = {
    first_name: string,
    last_name: string
    name: string,
    id: number,
    class: string,
}
export type Role = "admin" | "user"
export type Team = {
    name: string
    code: string,
    description: string,
    members: {user: User, role: Role}[]
}