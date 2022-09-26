import Activity from "components/Activity";
import { Team } from "types/models";

// Function to convert date string between timezones
// Language: typescript



/**
 * Team Manager Component
 * 
 * Manages the team memberships, activity, and other team related data.
 *  
 */
const TeamManager = ({ team }: { team: Team }) => {
  return (
    <div className="text-center">
      <h1>Team Manager</h1>
      <h3 className="font-bold text-3xl">{team.name} - {team.code}</h3>
      <p>{team.description}</p>
      <h4>Csapat tagjai</h4>
      <table className="mx-auto border-4 border-gray-400 hover:border-gray-700 border-separate rounded-b-xl transition-colors duration-500">
        <thead>
          <tr>
            <th>Név</th>
            <th>Osztály</th>
            <th>Szerep</th>
          </tr>
        </thead>
        <tbody>
          {team.members.map((member) => (
            <tr>
              <td>{member.user.first_name} {member.user.last_name}</td>
              <td>{member.user.class}</td>
              <td>{member.role}</td>
              
            </tr>
          ))}
        </tbody>
      </table>
      <h4>Csapat aktivitása</h4>
      <Activity name="StarCraft">Yay</Activity>
      <Activity name="Karamu">Yay</Activity>
    </div>
    
  );
};
export default TeamManager;
