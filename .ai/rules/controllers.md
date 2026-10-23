# Controllers & HTTP Rules

**Applies to**: `app/Http/**`

- **Validation**: Validate all HTTP input through dedicated Form Request classes in `App\Http\Requests\Admin`. Do not call `$request->validate()` inline within controllers. Table query parameters must extend `DataTableRequest`.
- **Admin Responses**: Return redirection and AJAX feedback using `AdminFeedback::success($request, $message, $destination)`. Listing endpoints support server-side DataTables via a companion `data(DataTableRequest $request): JsonResponse` method rendering Blade cell partials.
- **Controller Queries**: Controllers query Eloquent directly using `when()` query filters. Do not create an unnecessary repository or service layer.
