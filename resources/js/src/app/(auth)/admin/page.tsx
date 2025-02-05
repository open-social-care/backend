import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";

export default function page() {
  return (
    <>
      <Heading h1>Admin H1</Heading>

      <Paper className="mt-4">
        <Heading h2>Titulo H2</Heading>
        <Heading h3>Titulo H3</Heading>
        Conteudo
      </Paper>
    </>
  );
}
