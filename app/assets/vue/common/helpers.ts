import countries_fr from "./countries_fr";
import countries_en from "./countries_en";

export function truncate(value: string | null, length: number) {
  if (!value) {
    return "";
  }
  return value.length > length ? value.substr(0, length) + "..." : value;
}

export function prettyBytes(value: number): string {
  let str;
  if (value >= 1024) {
    str = Math.ceil(value / 1024) + "GB";
  } else {
    str = value + "MB";
  }
  return str;
}

export function prettyCPUWeight(value: number): number {
  return Math.ceil(value / 100);
}

export function chunk(arr: Array<any>, len: number) {
  const chunks = [];
  let i = 0;
  const n = arr.length;

  while (i < n) {
    chunks.push(arr.slice(i, (i += len)));
  }

  return chunks;
}

export function getCurrencyCharByIsoCode(isoCode: string) {
  let symbol;

  switch (isoCode) {
  case "EUR":
    symbol = "€";
    break;
  case "USD":
  case "CAD":
    symbol = "$";
    break;
  case "GBP":
    symbol = "£";
    break;
  case "JPY":
    symbol = "¥";
    break;
  case "RUB":
    symbol = "₽";
    break;
  default:
    console.log("Unknown currency ISO code " + isoCode + ".");
    symbol = isoCode;
  }

  return symbol;
}

export function getCountriesByLocale(locale: string): Array<any> {
  if (locale === "fr") {
    return countries_fr;
  }

  if (locale === "en") {
    return countries_en;
  }
}

export class Throttle {
  lastTime = 0;

  run(func: any, timeFrame: number, ...args: any[]) {
    let now = Date.now();
    if (now - this.lastTime >= timeFrame) {
      func.apply(this, args);
      this.lastTime = now;
    }
  }
}

export function nl2br(str: string, is_json: boolean | undefined) {
  let breakTag = is_json || typeof is_json === "undefined" ? "<br />" : "<br>";
  return (str + "").replace(
    /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
    "$1" + breakTag + "$2"
  );
}
