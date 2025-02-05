import "@testing-library/jest-dom";
import { render, screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import Input from "./Input";

test("input should be rendered without label", async () => {
  render(<Input />);

  const input = screen.getByTestId("input");

  const label = screen.queryAllByTestId("label");

  expect(label).toMatchObject([]);

  expect(input).toBeVisible();
});

test("input should be rendered with label", async () => {
  render(<Input label="label example" />);

  expect(screen.getByTestId("label")).toHaveTextContent("label example");
});

test("input must allow text to be written", async () => {
  render(<Input />);

  const input = screen.getByTestId("input");

  await userEvent.type(input, "my-text");

  expect(input).toHaveValue("my-text");
});

test("input must show field error", async () => {
  const fieldError = "This text is not valid.";

  render(<Input errors={[fieldError]} />);

  const error = screen.getByTestId("errors").getElementsByTagName("p")[0];

  expect(error.textContent).toEqual(fieldError);

  expect(error).toHaveClass(/text-red-400/);
});
