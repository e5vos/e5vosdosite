import "style/App.scss";
import { Provider } from "react-redux";
import store from "lib/store";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { BaseLayout } from "templates";
import React from "react";
import Loader from "components/UIKit/Loader";
import TeamsPage from "pages/team";
import TeamPage from "pages/team/[teamcode]";
import EventsPage from "pages/event";
import EventPage from "pages/event/[eventid]";

const Home = React.lazy(() => import("pages/Home"));
const Error = React.lazy(() => import("components/Error"));

function App() {
  return (
    <Provider store={store}>
      <BrowserRouter>
        <BaseLayout>
          <React.Suspense fallback={<Loader />}>
            <Routes>
              <Route path="/">
                <Route index element={<Home />} />
                <Route path="csapat">
                  <Route index element={<TeamsPage />} />
                  <Route path=":teamid">
                    <Route index element={<TeamPage />} />
                    <Route path="admin" element={<>TeamAdminPage</>} />
                  </Route>
                  <Route path="uj" element={<>NewTeamPage</>} />
                  <Route path="admin" element={<>TeamAdminsPage</>} />
                </Route>
                <Route path="esemeny">
                  <Route index element={<EventsPage />} />
                  <Route path=":eventid">
                    <Route index element={<EventPage />} />
                    <Route path="kezel">
                      <Route index element={<>ManageEventPage</>} />
                      <Route path="szerkeszt" element={<>EditEventPage</>} />
                      <Route path="scanner" element={<>ScannerPage</>} />
                      <Route path="admin" element={<>ManageEventAdminPage</>} />
                    </Route>
                  </Route>
                  <Route path="kezel">
                    <Route index element={<>ManageEventsPage</>} />
                    <Route path="admin" element={<>EventAdminsPage</>} />
                  </Route>
                </Route>
                <Route path="eloadas">
                  <Route index element={<>LecturePage</>} />
                  <Route
                    path=":presentationCode"
                    element={<>PresentationAttendancePage</>}
                  />
                  <Route path="kezel">
                    <Route index element={<>PresentationAttendancePage</>} />
                    <Route path="admin" element={<>TeamAdminPage</>} />
                  </Route>
                </Route>

                <Route path="*" element={<Error code={404} />} />
              </Route>
            </Routes>
          </React.Suspense>
        </BaseLayout>
      </BrowserRouter>
    </Provider>
  );
}

export default App;
