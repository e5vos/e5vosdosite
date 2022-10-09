import Activity from "components/Activity";
import { api } from "lib/api";
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
  
  const {data: activitys} = api.useGetTeamActivityQuery(team.code);

  const deleteMember = (memberId: number) => {}

  return (
    <div className="text-center">
      <h1>Team Manager</h1>
      <h3 className="font-bold text-3xl">
        {team.name} - {team.code}
      </h3>
      <p>{team.description}</p>
      <h4>Csapat tagjai</h4>
      <div className="flex justify-center">
        <div className="flex-1 max-w-3xl p-1.5">
          <div className="overflow-hidden min-w-full border-2 border-gray-400 border-separate rounded-b-xl transition-colors duration-500">
            <table className="min-w-full divide-y divide-gray-200 ">
              <thead className="bg-gray-100">
                <tr>
                  <th>Név</th>
                  <th>Osztály</th>
                  <th>Szerep</th>
                  <th>Művelet</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-200">
                {team.members.map((member) => (
                  <tr>
                    <td className="mx-auto py-2 text-sm font-medium text-center whitespace-nowrap">
                      {member.user.first_name} {member.user.last_name}
                    </td>
                    <td className="mx-auto py-2 text-sm font-medium text-center whitespace-nowrap">
                      {member.user.class}
                    </td>
                    <td className="mx-auto py-2 text-sm font-medium text-center whitespace-nowrap">
                      {member.role}
                    </td>
                    {/* line below should only appear if user is admin of his team*/}
                    <td>
                      <span
                        className="mx-auto py-2 text-sm font-medium text-red-500 hover:text-red-700"
                        onClick={() => deleteMember(member.user.id)}
                      >
                        Delete
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
              {/* TODO
                    <tbody className="divide-y divide-gray-200">
                        <tr>
                          <td className="mx-auto py-2 text-sm font-medium text-center whitespace-nowrap">
                            <input className="" type="text" placeholder="Név"></input>
                          </td>
                          <td className="mx-auto py-2 text-sm font-medium text-center whitespace-nowrap">
                            <input className="" type="text" placeholder="Osztály"></input>
                          </td>
                          <td className="mx-auto py-2 text-sm font-medium text-center whitespace-nowrap">
                            <select className="" defaultValue="admin">
                                <option>admin</option>
                                <option>member</option>
                            </select>
                          </td>
                          
                          <td><a className="mx-auto py-2 text-sm font-medium text-green-500 hover:text-green-700"href="#">Add</a></td>
                        </tr>
                    </tbody>
                    */}
            </table>
          </div>
        </div>
      </div>
      <h4>Csapat aktivitása</h4>
      {activitys?.map((activity) => <Activity name={activity.event.name} attendance={activity.attendance} />)}
    </div>
  );
};
export default TeamManager;
