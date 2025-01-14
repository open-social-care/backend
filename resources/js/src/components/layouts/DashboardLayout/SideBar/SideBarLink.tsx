"use client";

import Link, { LinkProps } from "next/link";
import { usePathname } from "next/navigation";
import { twMerge } from "tailwind-merge";
import { useDashboardLayoutContext } from "../_context";

type SideBarLinkProps = {
  icon: React.ReactNode;
} & LinkProps &
  React.ComponentPropsWithoutRef<"a">;

export default function SideBarLink({ children, className, icon, ...rest }: SideBarLinkProps) {
  const pathname = usePathname();

  const { hideSidebar } = useDashboardLayoutContext();

  const isActive = pathname == rest.href;

  return (
    <Link
      className={twMerge(
        "flex transform items-center rounded-md px-3 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700",
        isActive && "bg-teal-100 text-teal-600",
        className,
      )}
      {...rest}
      onClick={() => {
        hideSidebar();
      }}
    >
      <span className="h-5 w-5">{icon}</span>

      <p
        className="text-md mx-2 font-normal"
        data-testid="label"
      >
        {children}
      </p>
    </Link>
  );
}
