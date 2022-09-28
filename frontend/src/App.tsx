import "style/App.scss";
import { Provider } from "react-redux";
import store from "lib/store";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { BaseLayout } from "templates";
import React from "react";
import Loader from "components/UIKit/Loader";


const Home = React.lazy(()=>import("pages/Home"))
const Error = React.lazy(()=>import("components/Error"))

function App() {
  

  return (
    <Provider store={store}>
      <BrowserRouter>
        <BaseLayout>
          <React.Suspense fallback={<Loader/>}>
            <Routes>
              <Route path="/" element={<Home />} />
              <Route path="*" element={<Error code={404}  />} />
            </Routes>
          </React.Suspense>
        </BaseLayout>
      </BrowserRouter>
    </Provider>
  );
}

export default App;
