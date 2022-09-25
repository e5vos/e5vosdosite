import "style/App.scss";
import { Provider } from "react-redux";
import store from "lib/store";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { BaseLayout } from "templates";
import Home from "routes/Home";

function App() {
  return (
    <Provider store={store}>
      <BrowserRouter>
        <BaseLayout>
          <Routes>
            <Route path="/" element={<Home />} />
          </Routes>
        </BaseLayout>
      </BrowserRouter>
    </Provider>
  );
}

export default App;
