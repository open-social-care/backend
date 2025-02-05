import "@testing-library/jest-dom";
import { render } from "@testing-library/react";
import FlashMessage from "./FlashMessage";

test("error flash message should be rendered in red", async () => {
  const message = "This is a error message.";

  const { container } = render(
    <FlashMessage
      data-testid="fm"
      type="error"
      message={message}
    />,
  );

  const { firstChild: element } = container;

  expect(element).toHaveTextContent(message);

  expect(element).toHaveClass(/bg-red-500/);
});

test("success flash message should be rendered in green", async () => {
  const message = "This is a success message.";

  const { container } = render(
    <FlashMessage
      type="success"
      message={message}
    />,
  );

  const { firstChild: element } = container;

  expect(element).toHaveTextContent(message);

  expect(element).toHaveClass(/bg-emerald-500/);
});
