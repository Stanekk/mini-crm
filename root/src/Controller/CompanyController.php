<?php

namespace App\Controller;

use App\Dto\Company\CreateCompanyRequestDto;
use App\Entity\Company;
use App\Mapper\CompanyMapper;
use App\Repository\CompanyRepository;
use App\Service\CompanyService;
use App\Service\PaginationService;
use App\Validator\PatchCompanyValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompanyController extends AbstractController
{
    private CompanyService $companyService;
    private CompanyMapper $companyMapper;
    private CompanyRepository $companyRepository;
    private ValidatorInterface $validator;
    private PaginationService $paginationService;

    public function __construct(CompanyService $companyService, CompanyMapper $companyMapper, CompanyRepository $companyRepository, ValidatorInterface $validator, PaginationService $paginationService)
    {
        $this->companyService = $companyService;
        $this->companyMapper = $companyMapper;
        $this->companyRepository = $companyRepository;
        $this->validator = $validator;
        $this->paginationService = $paginationService;
    }

    #[Route('/api/companies', name: 'app_company_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $page = max(1, $request->query->getInt('page', 1));
        $qb = $this->companyRepository->createQueryBuilder('company');

        $result = $this->paginationService->paginate($qb, $page,
            fn (Company $company) => $this->companyMapper->toDto($company)
        );

        return new JsonResponse($result, Response::HTTP_OK);
    }

    #[Route('/api/companies', name: 'app_company_create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['Default'])] CreateCompanyRequestDto $dto): JsonResponse
    {
        $company = $this->companyService->create($dto);
        $responseDto = $this->companyMapper->toDto($company);

        return new JsonResponse($responseDto, Response::HTTP_CREATED);
    }

    #[Route('/api/companies/{id}', name: 'app_company_details', methods: ['GET'])]
    public function details(int $id): JsonResponse
    {
        $company = $this->companyService->getCompanyById($id);
        if (!$company) {
            throw new NotFoundHttpException('Company not found.');
        }

        $companyDto = $this->companyMapper->toDto($company);

        return new JsonResponse($companyDto, Response::HTTP_OK);
    }

    #[Route('/api/companies/{id}', name: 'app_company_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $company = $this->companyService->getCompanyById($id);
        if (!$company) {
            throw new NotFoundHttpException('Company not found.');
        }

        $this->companyService->deleteCompany($company);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/companies/{id}', name: 'app_company_edit', methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $company = $this->companyService->getCompanyById($id);
        $data = $request->toArray();
        if (!$company) {
            throw new NotFoundHttpException('Company not found.');
        }

        $errors = $this->validator->validate(new PatchCompanyValidator($data, $this->validator));
        if (count($errors) > 0) {
            throw new ValidationFailedException(new \stdClass(), $errors);
        }

        $company = $this->companyService->updateCompany($company, $data);

        $companyDto = $this->companyMapper->toDto($company);

        return new JsonResponse($companyDto, Response::HTTP_OK);
    }
}
