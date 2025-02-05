import { render, screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";

import Button from "./Button";

jest.mock("react-dom", () => ({
  ...jest.requireActual("react-dom"),
  useFormStatus: () => ({ pending: false }),
}));

test("renders the button with the correct text", () => {
  render(<Button>Button</Button>);
  const buttonElement = screen.getByText("Button");
  expect(buttonElement).toBeInTheDocument();
});

test("applies the correct styles", () => {
  render(<Button>Button</Button>);
  const buttonElement = screen.getByTestId("button");
  expect(buttonElement).toHaveClass("bg-teal-500"); // Verifica uma das classes
});

test("button should be accept click event", async () => {
  const onClick = jest.fn();

  const component = render(<Button onClick={onClick} />);

  const btn = await component.findByTestId("button");

  await userEvent.click(btn);

  expect(onClick).toHaveBeenCalledTimes(1);
});
