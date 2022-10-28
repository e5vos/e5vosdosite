import {
  TypedUseSelectorHook,
  useDispatch as originalDispatchHook,
  useSelector as originalSelectorHook,
} from "react-redux/es/exports";
import { createBrowserHistory } from "history";
import {
  $CombinedState,
  combineReducers,
  configureStore,
} from "@reduxjs/toolkit";
import { routerMiddleware } from "connected-react-router";
import {
  persistReducer,
  persistStore,
  FLUSH,
  REHYDRATE,
  PAUSE,
  PERSIST,
  PURGE,
  REGISTER,
} from "redux-persist";
import storage from "redux-persist/lib/storage";
import AuthReducer from "reducers/authReducer";
import { api } from "./api";
import { setupListeners } from "@reduxjs/toolkit/dist/query";

export const history = createBrowserHistory();

const rootReducer = persistReducer(
  { key: "root", storage: storage, blacklist: [api.reducerPath] },
  combineReducers({
    auth: AuthReducer,
    [api.reducerPath]: api.reducer,
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
      .concat(api.middleware),
});
setupListeners(store.dispatch);
export type RootState = ReturnType<typeof rootReducer>;
export type AppDispatch = typeof store.dispatch;

export const useDispatch: () => AppDispatch = originalDispatchHook;
export const useSelector: TypedUseSelectorHook<RootState> =
  originalSelectorHook;
export default store;

export const persistor = persistStore(store);
