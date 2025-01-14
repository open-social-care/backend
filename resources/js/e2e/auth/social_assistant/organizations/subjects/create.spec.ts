import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { expect, test } from "@playwright/test";

test("create subject", async ({ page }) => {
  await page.goto("http://127.0.0.1:3000/social-assistant/organizations/id/subjects/create");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page.getByText(/Verifique os erros nos campos!/)).toBeVisible();

  await page.locator('input[name="name"]').fill("Fulano");

  await page.getByPlaceholder("00/00/0000").fill("10/10/1980");

  await page.getByPlaceholder(t("labels.nationality")).fill("Brasileira");

  await page.getByPlaceholder("(00) 00000-0000").fill("(11) 1111-1111");

  await page.getByPlaceholder("000.000.000-00").fill("010.519.760-22");

  await page.getByPlaceholder("RG").fill("0105192");

  await page.locator('select[name="skin_color"]').selectOption("medium-black");

  await page.getByPlaceholder(t("labels.father_name")).fill("João");

  await page.getByPlaceholder(t("labels.mother_name")).fill("Maria");

  await page.locator('select[name="state_id"]').selectOption("18");

  await page.locator('select[name="city_id"]').selectOption("4045");

  await page.getByPlaceholder(t("labels.street")).fill("Nome da rua");

  await page.getByPlaceholder(t("labels.number")).fill("01");

  await page.getByPlaceholder(t("labels.district")).fill("teste");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page).toHaveURL("/social-assistant/organizations/id/subjects/");
});
