import { createSlice, PayloadAction } from "@reduxjs/toolkit";
import { User } from "types/models";

const initialState: {
  token: string;
  user: User | null;
  csrf: string;
} = {
  token: "",
  csrf: "",
  user: null,
};

export const authSlice = createSlice({
  name: "auth",
  initialState: initialState,
  reducers: {
    setToken: (state, action: PayloadAction<string>) => {
      state.token = action.payload;
    },
    setCSRF: (state, action: PayloadAction<string>) => {
      state.csrf = action.payload;
    },
  },
});

export default authSlice.reducer;
