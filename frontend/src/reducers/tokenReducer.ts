import { createSlice, PayloadAction } from "@reduxjs/toolkit";


export const tokenSlice = createSlice({
    name: "auth",
    initialState: {
        token: "",
    },
    reducers: {
        setToken: (state, action: PayloadAction<string>) => {
            state.token = action.payload;
        }
    }
})


export default tokenSlice.reducer;