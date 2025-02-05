"use client";

import { twMerge } from "tailwind-merge";
import DashboardLayoutContextProvider from "./_context";

export default function Root({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"div">) {
  return (
    <DashboardLayoutContextProvider>
      <div
        className={twMerge("flex min-h-screen flex-col  bg-gray-50", className)}
        {...rest}
      >
        {children}
      </div>
    </DashboardLayoutContextProvider>
  );
}
