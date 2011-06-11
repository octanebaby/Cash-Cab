<% @page contentType="text/xml" %>
<Response>
    <Say>A customer at the number <%= request.getParameter("number") %> is calling</Say>
    <!-- you may substitute another number to call here -->
    <Dial><%= request.getParameter("number") %></Dial>
</Response>
