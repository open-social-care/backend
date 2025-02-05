import { TranslateOptions } from "i18n-js";
import i18n from "./i18n";
import translations from "./translations/pt-BR.json";

//------------------ Custom typing scopes (from method 2 of https://stackoverflow.com/a/58308279)

type DeepKeys<T, S extends string> = T extends object
  ? S extends `${infer I1}.${infer I2}`
    ? I1 extends keyof T
      ? // fix issue allowed last dot
        T[I1] extends object
        ? `${I1}.${DeepKeys<T[I1], I2>}`
        : keyof T & string
      : keyof T & string
    : S extends keyof T
      ? `${S}`
      : keyof T & string
  : "";

export function t<S extends string>(
  p: DeepKeys<(typeof translations)["pt-BR"], S>,
  options?: TranslateOptions,
): string {
  return i18n.t(p, options);
}
