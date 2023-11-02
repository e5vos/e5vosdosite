import React, { useEffect } from "react";
import { Provider } from "react-redux";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { PersistGate } from "redux-persist/integration/react";
import "style/App.pcss";
import { BaseLayout } from "templates";

import refreshCSRF from "lib/csrf";
import store, { persistor } from "lib/store";

import Loader from "components/UIKit/Loader";

// Generic Routes
const Home = React.lazy(() => import("pages/Home"));
const Error = React.lazy(() => import("components/Error"));

// Misc

const LegalPage = React.lazy(() => import("pages/Legal"));

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
const ManageEventPage = React.lazy(
    () => import("pages/event/[eventid]/manage/index"),
);
const EventsPage = React.lazy(() => import("pages/event"));
const EventPage = React.lazy(() => import("pages/event/[eventid]/index"));
const ScannerPage = React.lazy(
    () => import("pages/event/[eventid]/manage/scanner"),
);

const EditEventPage = React.lazy(
    () => import("pages/event/[eventid]/manage/edit"),
);

const eventRoutes = (
    <Route path="esemeny">
        <Route index element={<EventsPage />} />
        <Route path=":eventid">
            <Route index element={<EventPage />} />
            <Route path="kezel">
                <Route index element={<ManageEventPage />} />
                <Route path="szerkeszt" element={<EditEventPage />} />
                <Route path="scanner" element={<ScannerPage />} />
                <Route path="admin" element={<>ManageEventAdminPage</>} />
            </Route>
        </Route>
        <Route path="kezel">
            <Route index element={<ManageEventsPage />} />
        </Route>
    </Route>
);

// Presentations

const PresentationPage = React.lazy(() => import("pages/presentation"));
const PresentationsManagePage = React.lazy(
    () => import("pages/presentation/manage"),
);
const AttendanceSheet = React.lazy(() => import("pages/attendance"));

const presentationRoutes = (
    <Route path="eloadas">
        <Route index element={<PresentationPage />} />
        <Route path="kezel">
            <Route index element={<PresentationsManagePage />} />
            <Route path=":eventid">
                <Route index element={<AttendanceSheet />} />
                <Route path="scanner" element={<ScannerPage />} />
            </Route>
        </Route>
    </Route>
);

// Admin

const AdminsPage = React.lazy(() => import("pages/admin"));
const SlotsPage = React.lazy(() => import("pages/admin/slot"));
const SlotsCreatePage = React.lazy(() => import("pages/admin/slot/create"));
const SlotsEditPage = React.lazy(
    () => import("pages/admin/slot/[slotid]/edit"),
);
const adminRoutes = (
    <Route path="admin">
        <Route index element={<AdminsPage />} />
        <Route path="sav">
            <Route index element={<SlotsPage />} />
            <Route path="uj" element={<SlotsCreatePage />} />
            <Route path=":slotid">
                <Route path="kezel" element={<SlotsEditPage />} />
            </Route>
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

// const TermsPage = React.lazy(() => import("pages/terms"));
const PrivacyPolicyPage = React.lazy(() => import("pages/privacypolicy"));

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
                                    {adminRoutes}
                                    {authRoutes}
                                    <Route
                                        path="/privacypolicy"
                                        element={<PrivacyPolicyPage />}
                                    />
                                    <Route
                                        path="/legal"
                                        element={<LegalPage />}
                                    />
                                    <Route
                                        path="*"
                                        element={<Error code={404} />}
                                    />
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
