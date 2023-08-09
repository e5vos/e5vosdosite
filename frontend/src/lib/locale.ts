export type LocaleLanguages = "hu" | "en";
export const DefaultLocale = "hu" as const;

const SelectedLocale = DefaultLocale;

export const Locale = <
  T extends { [key in LocaleLanguages]: T[typeof DefaultLocale] }
>(
  l: T
) => {
  return l[SelectedLocale];
};

export default Locale;
