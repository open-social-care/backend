import { twMerge } from "tailwind-merge";

export default function Text({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"p">) {
  return (
    <p
      className={twMerge("text-gray-700", className)}
      {...rest}
    >
      {children}
    </p>
  );
}
