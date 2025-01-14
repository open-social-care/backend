import { VBox } from "@/components/containers";
import { Suspense } from "react";
import { twMerge } from "tailwind-merge";

type SkeletonProps = React.ComponentPropsWithoutRef<"div"> & {
  length?: number;
};

export default function Skeleton({ length, className, children, ...rest }: SkeletonProps) {
  return (
    <Suspense
      fallback={
        <>
          {Array.from({ length: length || 1 }).map((_, index) => (
            <div
              key={index}
              className={twMerge(
                "mx-auto mt-1 h-96 w-full animate-pulse overflow-hidden rounded-lg bg-white shadow-md",
                className,
              )}
              {...rest}
            >
              <VBox className="gap-0 p-4">
                <p className="h-3 w-36 rounded-lg bg-gray-200" />
                <p className="mt-2 h-3 w-56 rounded-lg bg-gray-200" />
              </VBox>
            </div>
          ))}
        </>
      }
    >
      {children}
    </Suspense>
  );
}
