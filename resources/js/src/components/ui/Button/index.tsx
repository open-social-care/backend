import { twMerge } from "tailwind-merge";

export default function Button({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"a">) {
  return (
    <a
      {...rest}
      className={twMerge(
        "rounded-lg bg-teal-500 px-6 py-2 leading-5 text-white transition-colors duration-300 hover:bg-teal-600 focus:bg-teal-600 focus:outline-none",
        className,
      )}
    >
      {children}
    </a>
  );
}
