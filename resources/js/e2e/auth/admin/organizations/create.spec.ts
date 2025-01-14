import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { expect, test } from "@playwright/test";

test("create organization", async ({ page }) => {
  await page.goto("http://127.0.0.1:3000/admin/organizations/create");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page.getByText(/Verifique os erros nos campos!/)).toBeVisible();

  await page.getByPlaceholder(t("labels.name")).fill("Conselho de Guarapuava");

  await page.getByPlaceholder("(00) 00000-0000").fill("(11) 11111-11111");

  await page.getByTestId(testIDs.SELECT).selectOption("cnpj");

  await page.getByPlaceholder(t("labels.document")).fill("32.997.945/0001-16");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page).toHaveURL("/admin/organizations");
});
