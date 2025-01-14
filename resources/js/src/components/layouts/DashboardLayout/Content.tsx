import { twMerge } from "tailwind-merge";

export default function Content({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"div">) {
  return (
    <div
      className={twMerge("mb-5 flex pb-4 pt-16", className)}
      {...rest}
    >
      {children}
    </div>
  );
}
