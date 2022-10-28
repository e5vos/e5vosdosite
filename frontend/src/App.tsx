import "style/App.pcss";
import { Provider } from "react-redux";
import store, { persistor } from "lib/store";
import { PersistGate } from "redux-persist/integration/react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { BaseLayout } from "templates";
import React from "react";
import Loader from "components/UIKit/Loader";
import LogoutPage from "pages/logout";
import StudentCodePage from "pages/studentcode";


const Home = React.lazy(() => import("pages/Home"));
const Error = React.lazy(() => import("components/Error"));
const PresentationPage = React.lazy(() => import("pages/presentation"));
const TeamsPage = React.lazy(() => import("pages/team"));
const TeamPage = React.lazy(() => import("pages/team/[teamcode]"));
const EventsPage = React.lazy(() => import("pages/event"));
const EventPage = React.lazy(() => import("pages/event/[eventid]"));
const LoginPage = React.lazy(() => import("pages/login"));

function App() {
  return (
    <Provider store={store}>
      <PersistGate persistor={persistor} loading={<Loader />}>
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
                        <Route
                          path="admin"
                          element={<>ManageEventAdminPage</>}
                        />
                      </Route>
                    </Route>
                    <Route path="kezel">
                      <Route index element={<>ManageEventsPage</>} />
                      <Route path="admin" element={<>EventAdminsPage</>} />
                    </Route>
                  </Route>
                  <Route path="eloadas">
                    <Route index element={<PresentationPage />} />
                    <Route
                      path=":presentationCode"
                      element={<>PresentationAttendancePage</>}
                    />
                    <Route path="kezel">
                      <Route index element={<>PresentationAttendancePage</>} />
                      <Route path="admin" element={<>TeamAdminPage</>} />
                    </Route>
                  </Route>
                  <Route path="/login" element={<LoginPage />} />
                  <Route path="/studentcode" element={<StudentCodePage />} />
                  <Route path="/logout" element={<LogoutPage/>} />
                  <Route path="*" element={<Error code={404} />} />
                </Route>
              </Routes>
            </React.Suspense>
          </BaseLayout>
        </BrowserRouter>
      </PersistGate>
    </Provider>
  );
}

export default App;
