import { Team } from "types/models";

const TeamManager = ({ team }: { team: Team }) => {
  return (
    <div>
      <h3>{team.name}</h3>
      <p>{team.description}</p>
      <h4>Csapat tagjai</h4>
      <table>
        <thead>
          <tr>
            <th>Név</th>
            <th>Szerep</th>
          </tr>
        </thead>
        <tbody>
          {team.members.map((member) => (
            <tr>
              <td>{member.user.name}</td>
              <td>{member.role}</td>
            </tr>
          ))}
        </tbody>
      </table>
      <h4>Csapat aktivitása</h4>
      
    </div>
  );
};
export default TeamManager;
