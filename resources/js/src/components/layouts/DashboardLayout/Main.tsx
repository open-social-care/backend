import { twMerge } from "tailwind-merge";

export default function Main({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"main">) {
  return (
    <main
      className={twMerge("w-full px-4 pt-4 md:ml-64 md:px-6", className)}
      {...rest}
    >
      {children}
    </main>
  );
}
