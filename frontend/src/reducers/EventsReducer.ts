import { createSlice } from "@reduxjs/toolkit";

interface EventsState {

}
const initialState : EventsState = {} 

export const eventSlice = createSlice({
    name: "events",
    initialState,
    reducers:{}

})

export default eventSlice.reducer;