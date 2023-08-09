import { combineReducers, configureStore } from "@reduxjs/toolkit";
import { setupListeners } from "@reduxjs/toolkit/dist/query";
import { routerMiddleware } from "connected-react-router";
import { createBrowserHistory } from "history";
import {
  TypedUseSelectorHook,
  useDispatch as originalDispatchHook,
  useSelector as originalSelectorHook,
} from "react-redux/es/exports";
import AuthReducer from "reducers/authReducer";
import {
  FLUSH,
  PAUSE,
  PERSIST,
  PURGE,
  REGISTER,
  REHYDRATE,
  persistReducer,
  persistStore,
} from "redux-persist";
import storage from "redux-persist/lib/storage";

import baseAPI from "./api";

export const history = createBrowserHistory();

const rootReducer = persistReducer(
  { key: "root", storage: storage, blacklist: [baseAPI.reducerPath] },
  combineReducers({
    auth: AuthReducer,
    [baseAPI.reducerPath]: baseAPI.reducer,
  })
);

const store = configureStore({
  reducer: rootReducer,
  devTools: process.env.NODE_ENV === "development",
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware({
      serializableCheck: {
        ignoredActions: [FLUSH, REHYDRATE, PAUSE, PERSIST, PURGE, REGISTER],
      },
    })
      .concat(routerMiddleware(history))
      .concat(baseAPI.middleware),
});
setupListeners(store.dispatch);
export type RootState = ReturnType<typeof rootReducer>;
export type AppDispatch = typeof store.dispatch;

export const useDispatch: () => AppDispatch = originalDispatchHook;
export const useSelector: TypedUseSelectorHook<RootState> =
  originalSelectorHook;
export default store;

export const persistor = persistStore(store);
