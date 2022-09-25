import Navbar from "components/UIKit/Navbar";

const CustomNavbar = () =>{
    return <Navbar brand={<Navbar.Brand>Hi</Navbar.Brand>}>
        <Navbar.Link href="">Test</Navbar.Link>
        <Navbar.Dropdown toggle={<>Welcome</>}>
            <Navbar.Dropdown.Item>Test</Navbar.Dropdown.Item>
        </Navbar.Dropdown>
        <Navbar.Link href="">Test</Navbar.Link>

    </Navbar>
}
export default CustomNavbar;