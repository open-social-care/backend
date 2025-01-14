import Link, { LinkProps } from "next/link";
import { twMerge } from "tailwind-merge";

type ProfileLinkProps = {
  icon: React.ReactNode;
} & LinkProps &
  React.ComponentPropsWithoutRef<"a">;

export default function ProfileLink({ children, className, icon, ...rest }: ProfileLinkProps) {
  return (
    <Link
      className={twMerge(
        "flex transform items-center rounded-md px-3 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700",
        className,
      )}
      {...rest}
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
