import { twMerge } from "tailwind-merge";

type RootProps = {
  centered?: boolean;
} & React.ComponentPropsWithoutRef<"div">;

export default function Root({ children, className, centered, ...rest }: RootProps) {
  return (
    <div
      className={twMerge(
        "flex h-screen flex-col bg-gray-100 bg-[url('/images/landing-page-background.svg')]",
        centered && "items-center justify-center",
        className,
      )}
      {...rest}
    >
      {children}
    </div>
  );
}
