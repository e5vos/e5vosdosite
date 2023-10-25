import { PayloadAction, createSlice } from "@reduxjs/toolkit";

import { DefaultLocale, LocaleLanguages } from "lib/locale";

const initialState: {
    language: LocaleLanguages;
} = {
    language: DefaultLocale,
};

export const settingsSlice = createSlice({
    name: "settings",
    initialState: initialState,
    reducers: {
        setLanguage: (state, action: PayloadAction<LocaleLanguages>) => {
            state.language = action.payload;
        },
    },
});

export default settingsSlice.reducer;
