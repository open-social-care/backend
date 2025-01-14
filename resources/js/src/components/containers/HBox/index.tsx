import { twMerge } from "tailwind-merge";

export default function HBox({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"div">) {
  return (
    <div
      className={twMerge("flex flex-row gap-2", className)}
      {...rest}
    >
      {children}
    </div>
  );
}
