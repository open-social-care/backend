import { twMerge } from "tailwind-merge";

export default function Root({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"div">) {
  return (
    <div
      className={twMerge(
        "fixed z-50 flex h-16 w-full items-center justify-between border-b border-gray-200 bg-white px-2",
        className,
      )}
      {...rest}
    >
      {children}
    </div>
  );
}
