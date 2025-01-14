"use client";

import { twMerge } from "tailwind-merge";
import { useDashboardLayoutContext } from "../_context";

export default function Root({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"aside">) {
  const { sidebarVisible } = useDashboardLayoutContext();

  return (
    <aside
      className={twMerge(
        "fixed hidden h-full w-60 border-l border-r bg-white p-2 sm:w-64 md:flex",
        sidebarVisible ? "flex" : "hidden",
        className,
      )}
      {...rest}
    >
      <nav className="mt-2 w-full">{children}</nav>
    </aside>
  );
}
