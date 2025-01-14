import { twMerge } from "tailwind-merge";

import Button from "./Button";
import FlashMessage from "./FlashMessage";
import Input from "./Input";
import Select from "./Select";
import SelectQueryParam from "./SelectQueryParam";
import Switch from "./Switch";

export default function Form({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"form">) {
  return (
    <form
      className={twMerge("flex flex-col", className)}
      {...rest}
    >
      {children}
    </form>
  );
}

Form.FlashMessage = FlashMessage;
Form.Input = Input;
Form.Button = Button;
Form.Select = Select;
Form.SelectQueryParam = SelectQueryParam;
Form.Switch = Switch;
