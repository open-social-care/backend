import { testIDs } from "@/e2e/_testIDs";
import { twMerge } from "tailwind-merge";

export type SelectItem = {
  label: string;
  value: string;
};

export type SelectProps = {
  data: SelectItem[];
  label?: string;
  placeholder?: string;
  errors?: string[];
  withAsterisk?: boolean;
} & React.ComponentPropsWithoutRef<"select">;

export default function Select({
  data,
  label,
  placeholder,
  errors,
  withAsterisk,
  className,
  ...rest
}: SelectProps) {
  return (
    <div className={twMerge("mt-4 w-full", className)}>
      {label && (
        <label
          className="text-gray-700"
          htmlFor={rest.name}
          data-testid="label"
        >
          {label}

          {withAsterisk && <span className="ml-1 text-sm text-red-400">*</span>}
        </label>
      )}

      <select
        name={rest.name}
        data-testid={testIDs.SELECT}
        className="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-2 py-2 text-gray-700 focus:border-teal-500 focus:outline-none focus:ring focus:ring-teal-300 focus:ring-opacity-40"
        {...rest}
      >
        <option
          disabled
          value=""
          selected={!rest.defaultValue}
        >
          {placeholder || "--"}
        </option>

        {data.map((item) => (
          <option
            key={item.value}
            value={item.value}
          >
            {item.label}
          </option>
        ))}
      </select>

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
    </div>
  );
}
