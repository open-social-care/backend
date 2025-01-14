import useQueryParam from "@/hooks/useQueryParam";
import { fireEvent, render, screen } from "@testing-library/react";
import Pagination from ".";

jest.mock("@/hooks/useQueryParam", () => ({
  __esModule: true,
  default: jest.fn().mockReturnValue({
    value: "1",
    set: jest.fn(),
  }),
}));

describe("Pagination", () => {
  const disabledClass = "pointer-events-none";

  it("renders correctly with multiple pages", () => {
    const paginate = { current_page: 2, last_page: 5, total: 5, to: 1, per_page: 10 };
    render(<Pagination paginate={paginate} />);

    const currentPageElement = screen.getByText("2/5");
    expect(currentPageElement).toBeInTheDocument();
  });

  it("disables buttons when on the first page", () => {
    const paginate = { current_page: 1, last_page: 5, total: 5, to: 1, per_page: 10 };
    render(<Pagination paginate={paginate} />);

    const firstPageButton = screen.getByTestId("first-page-button");
    const prevPageButton = screen.getByTestId("prev-page-button");
    expect(firstPageButton).toHaveClass(disabledClass);
    expect(prevPageButton).toHaveClass(disabledClass);
  });

  it("disables buttons when on the last page", () => {
    const paginate = { current_page: 5, last_page: 5, total: 5, to: 1, per_page: 10 };
    render(<Pagination paginate={paginate} />);

    const lastPageButton = screen.getByTestId("last-page-button");
    const nextPageButton = screen.getByTestId("next-page-button");
    expect(lastPageButton).toHaveClass(disabledClass);
    expect(nextPageButton).toHaveClass(disabledClass);
  });

  it("calls set function when clicking on buttons", () => {
    const paginate = { current_page: 2, last_page: 5, total: 5, to: 1, per_page: 10 };
    const setMock = jest.fn();
    (useQueryParam as jest.Mock).mockReturnValue({ value: "2", set: setMock });

    render(<Pagination paginate={paginate} />);

    const firstPageButton = screen.getByTestId("first-page-button");
    fireEvent.click(firstPageButton);
    expect(setMock).toHaveBeenCalledWith("1");

    const prevPageButton = screen.getByTestId("prev-page-button");
    fireEvent.click(prevPageButton);
    expect(setMock).toHaveBeenCalledWith("1");

    const nextPageButton = screen.getByTestId("next-page-button");
    fireEvent.click(nextPageButton);
    expect(setMock).toHaveBeenCalledWith("3");

    const lastPageButton = screen.getByTestId("last-page-button");
    fireEvent.click(lastPageButton);
    expect(setMock).toHaveBeenCalledWith("5");
  });
});
