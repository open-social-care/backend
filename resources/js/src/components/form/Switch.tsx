import { twMerge } from "tailwind-merge";

type SwitchProps = {
  label?: string;
  errors?: string[];
  withAsterisk?: boolean;
} & React.ComponentPropsWithoutRef<"input">;

export default function Switch({
  label,
  name,
  errors,
  withAsterisk,
  className,
  ...rest
}: SwitchProps) {
  return (
    <div className={twMerge("mt-4 w-full", className)}>
      <label className="inline-flex cursor-pointer items-center">
        <input
          name={name}
          type="checkbox"
          className="peer sr-only"
          {...rest}
        />

        <div
          data-testid="checkbox"
          className="peer relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-teal-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rtl:peer-checked:after:-translate-x-full"
        ></div>

        <span className="ms-3 text-sm font-medium text-gray-900">{label}</span>

        <div data-testid="errors">
          {errors?.map((error, index) => (
            <p
              key={index}
              className="mt-1 text-sm text-red-400"
            >
              {error}
            </p>
          ))}
        </div>
      </label>
    </div>
  );
}
