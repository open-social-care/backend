"use client";

import useQueryParam from "@/hooks/useQueryParam";
import { t } from "@/lang";
import { twMerge } from "tailwind-merge";

export default function Search({ className }: React.ComponentPropsWithoutRef<"input">) {
  const { value, set } = useQueryParam({ queryParam: "search", paramsToDeleteOnSet: ["page"] });

  return (
    <input
      className={twMerge(
        "peer block w-full rounded-lg border py-[9px] pl-5 text-sm shadow-md outline-2 placeholder:text-gray-500 focus:border-teal-500 focus:outline-none focus:ring focus:ring-teal-300 focus:ring-opacity-40",
        className,
      )}
      placeholder={t("general_actions.search")}
      onChange={(e) => {
        set(e.target.value);
      }}
      defaultValue={value}
    />
  );
}
