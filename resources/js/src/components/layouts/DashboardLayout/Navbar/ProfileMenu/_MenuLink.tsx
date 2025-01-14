type MenuLinkProps = {
  href: string;
  title: string;
};

export default function MenuLink({ href, title }: MenuLinkProps) {
  return (
    <a
      href={href}
      className="block transform px-4 py-3 text-sm capitalize text-gray-600 transition-colors duration-300 hover:bg-gray-100"
    >
      {title}
    </a>
  );
}
