import { twMerge } from "tailwind-merge";

import { IMaskInput, IMaskInputProps } from "react-imask";

type InputProps = {
  label?: string;
  errors?: string[];
  withAsterisk?: boolean;
} & IMaskInputProps<HTMLInputElement>;

export default function Input({
  label,
  name,
  errors,
  withAsterisk,
  className,
  ...rest
}: InputProps) {
  return (
    <div className={twMerge("mt-4 w-full", className)}>
      {label && (
        <label
          className="text-gray-700"
          htmlFor={name}
          data-testid="label"
        >
          {label}

          {withAsterisk && <span className="ml-1 text-sm text-red-400">*</span>}
        </label>
      )}

      <IMaskInput
        name={name}
        data-testid="input"
        className="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-4 py-1.5 text-gray-700 focus:border-teal-500 focus:outline-none focus:ring focus:ring-teal-300 focus:ring-opacity-40"
        {...rest}
      />

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
