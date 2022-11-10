import "style/App.pcss";
import { Provider } from "react-redux";
import store, { persistor } from "lib/store";
import { PersistGate } from "redux-persist/integration/react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { BaseLayout } from "templates";
import React, { useEffect } from "react";
import Loader from "components/UIKit/Loader";
import refreshCSRF from "lib/csrf";

// Generic Routes
const Home = React.lazy(() => import("pages/Home"));
const Error = React.lazy(() => import("components/Error"));

// Misc

const LegalPage = React.lazy(() => import("pages/Legal"));
const AdminPage = React.lazy(() => import("pages/admin/e5nAdmin"));

// Teams
const TeamsPage = React.lazy(() => import("pages/team"));
const TeamPage = React.lazy(() => import("pages/team/[teamcode]"));

const teamRoutes = (
  <Route path="csapat">
    <Route index element={<TeamsPage />} />
    <Route path=":teamid">
      <Route index element={<TeamPage />} />
      <Route path="admin" element={<>TeamAdminPage</>} />
    </Route>
    <Route path="uj" element={<>NewTeamPage</>} />
    <Route path="admin" element={<>TeamAdminsPage</>} />
  </Route>
);

// Events

const ManageEventsPage = React.lazy(() => import("pages/event/manage"));
const EventsPage = React.lazy(() => import("pages/event"));
const EventPage = React.lazy(() => import("pages/event/[eventid]"));

const eventRoutes = (
  <Route path="esemeny">
    <Route index element={<EventsPage />} />
    <Route path=":eventid">
      <Route index element={<EventPage />} />
      <Route path="kezel">
        <Route index element={<ManageEventsPage />} />
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
);

// Presentations

const PresentationPage = React.lazy(() => import("pages/presentation"));
const PresentationManagePage = React.lazy(
  () => import("pages/presentation/manage")
);
const AttendanceSheet = React.lazy(() => import("pages/attendance"));

const presentationRoutes = (
  <Route path="eloadas">
    <Route index element={<PresentationPage />} />
    <Route path="kezel">
      <Route index element={<PresentationManagePage />} />
      <Route path="admin" element={<>TeamAdminPage</>} />
      <Route path=":eventid" element={<AttendanceSheet />} />
    </Route>
  </Route>
);

// Auth

const LoginPage = React.lazy(() => import("pages/login"));
const LogoutPage = React.lazy(() => import("pages/logout"));
const StudentCodePage = React.lazy(() => import("pages/studentcode"));
const authRoutes = (
  <Route>
    <Route path="/login" element={<LoginPage />} />,
    <Route path="/studentcode" element={<StudentCodePage />} />,
    <Route path="/logout" element={<LogoutPage />} />,
  </Route>
);

// Application body

function App() {
  useEffect(() => {
    refreshCSRF();
  }, []);

  return (
    <Provider store={store}>
      <PersistGate persistor={persistor} loading={<Loader />}>
        <BrowserRouter>
          <BaseLayout>
            <React.Suspense fallback={<Loader />}>
              <Routes>
                <Route path="/">
                  <Route index element={<Home />} />
                  {teamRoutes}
                  {eventRoutes}
                  {presentationRoutes}
                  {authRoutes}
                  <Route path="/legal" element={<LegalPage />} />
                  <Route path="/admin" element={<AdminPage />} />
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
