import React from "react";
import { twMerge } from "tailwind-merge";

export default function Badge({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"span">) {
  return (
    <span
      className={twMerge("w-fit rounded-lg bg-teal-100 p-1 px-2 text-sm text-teal-900", className)}
      {...rest}
    >
      {children}
    </span>
  );
}
