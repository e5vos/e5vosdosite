import {
  TypedUseSelectorHook,
  useDispatch as originalDispatchHook,
  useSelector as originalSelectorHook,
} from "react-redux/es/exports";
import { createBrowserHistory, History } from "history";
import { configureStore } from "@reduxjs/toolkit";
import { routerMiddleware, connectRouter } from "connected-react-router";

export const history = createBrowserHistory();

const rootReducer = (history: History) => ({
  router: connectRouter(history),
});

const store = configureStore({
  reducer: rootReducer(history),
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware().concat(routerMiddleware(history)),
});

export type RootState = ReturnType<typeof rootReducer>;
export type AppDispatch = typeof store.dispatch;

export const useDispatch: () => AppDispatch = originalDispatchHook;
export const useSelector: TypedUseSelectorHook<RootState> =
  originalSelectorHook;
export default store;
  