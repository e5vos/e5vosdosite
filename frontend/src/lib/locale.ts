export type LocaleLanguages = "hu" | "en";
export const DefaultLocale = "hu" as const;

const isLocaleLanguage = (value: string): value is LocaleLanguages => {
  return value === "hu" || value === "en";
};
const navigatorLocale = navigator.language.substring(0, 2);
const SelectedLocale = isLocaleLanguage(navigatorLocale)
  ? navigatorLocale
  : DefaultLocale;

export const Locale = <
  T extends { [key in LocaleLanguages]: T[typeof DefaultLocale] }
>(
  l: T
) => {
  console.log();
  return l[SelectedLocale];
};

export default Locale;
