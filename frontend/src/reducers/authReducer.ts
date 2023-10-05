import { PayloadAction, createSlice } from "@reduxjs/toolkit";

import { User } from "types/models";

const initialState: {
  token: string;
  user: User | null;
} = {
  token: "",
  user: null,
};

export const authSlice = createSlice({
  name: "auth",
  initialState: initialState,
  reducers: {
    setToken: (state, action: PayloadAction<string>) => {
      state.token = action.payload;
    },
    clearToken: (state) => {
      state.token = "";
    }
  },
});

export default authSlice.reducer;
